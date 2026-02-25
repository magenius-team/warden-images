const fs = require('fs');
const axios = require('axios');

const githubToken = process.env.GITHUB_TOKEN;
const packageName = process.env.PACKAGE_NAME;
const packageSlug = packageName.trim().toLowerCase();

const axiosInstance = axios.create({
    baseURL: 'https://api.github.com',
    headers: {
        'Authorization': `token ${githubToken}`,
        'Accept': 'application/vnd.github.v3+json'
    }
});

async function getLastPage() {
    try {
        const response = await axiosInstance.get(`/orgs/magenius-team/packages/container/${packageSlug}/versions?per_page=100`);
        const linkHeader = response.headers.link || '';
        const match = linkHeader.match(/page=(\d+)>; rel="last"/);
        const lastPageMatch = match ? match[1] : 1;
        return parseInt(lastPageMatch, 10);
    } catch (error) {
        console.error('Error fetching last page:', error);
        return 1;
    }
}

async function getTaggedVersionsFromLastPage() {
    let versions = new Set();
    try {
        let lastPage = await getLastPage();
        while (lastPage > 0) {
            const response = await axiosInstance.get(`/orgs/magenius-team/packages/container/${packageSlug}/versions?per_page=100&page=${lastPage}`);
            const tags = response.data.filter(version => version.metadata.container.tags.length !== 0).map(version => version.metadata.container.tags);
            tags.forEach(tagArray => tagArray.forEach(tag => {
                if (/^\d+(\.\d+)+$/.test(tag)) {
                    versions.add(tag);
                }
            }));
            lastPage--;
        }

        return [...versions].sort((a, b) => {
            const aParts = a.split('.').map(Number);
            const bParts = b.split('.').map(Number);

            for (let i = 0; i < Math.max(aParts.length, bParts.length); i++) {
                if ((aParts[i] || 0) < (bParts[i] || 0)) return -1;
                if ((aParts[i] || 0) > (bParts[i] || 0)) return 1;
            }
            return 0;
        });
    } catch (error) {
        console.error('Error fetching tagged versions:', error);
        return [];
    }
}

function updateReadme(versions) {
    const readmePath = 'README.md';
    const readmeContent = fs.readFileSync(readmePath, 'utf8');
    const versionList = versions.length ? versions.join(', ') : 'latest';
    const targetName = packageName.trim().toLowerCase();
    const lines = readmeContent.split('\n');
    let found = false;
    const serviceNames = [];

    const updatedLines = lines.map((line) => {
        const rowMatch = line.match(/^\|\s*([^|]+?)\s*\|\s*([^|]*?)\s*\|(.*)\|?\s*$/);
        if (!rowMatch) {
            return line;
        }

        const serviceName = rowMatch[1].trim();
        const rest = rowMatch[3];
        const normalizedServiceName = serviceName.toLowerCase();
        serviceNames.push(serviceName);

        if (normalizedServiceName !== targetName) {
            return line;
        }

        found = true;
        return `| ${serviceName} | ${versionList} |${rest}|`;
    });

    if (!found) {
        throw new Error(`Unable to find README table row for package: ${packageName}. Found services: ${serviceNames.join(', ')}`);
    }

    fs.writeFileSync(readmePath, updatedLines.join('\n'));
}

(async () => {
    try {
        const versions = await getTaggedVersionsFromLastPage();
        updateReadme(versions);
    } catch (error) {
        console.error('Error updating README.md:', error);
        process.exit(1);
    }
})();
