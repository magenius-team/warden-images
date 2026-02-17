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

function escapeRegExp(value) {
    return value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

function updateReadme(versions) {
    const readmePath = 'README.md';
    const readmeContent = fs.readFileSync(readmePath, 'utf8');
    const versionList = versions.length ? versions.join(', ') : 'latest';
    const escapedName = escapeRegExp(packageName.trim());
    const rowPattern = new RegExp(
        `^(\\|\\s*${escapedName}\\s*\\|\\s*)([^|]*?)(\\s*\\|\\s*.*\\|\\s*)$`,
        'mi'
    );

    const updatedContent = readmeContent.replace(rowPattern, `$1${versionList}$3`);
    if (updatedContent === readmeContent) {
        throw new Error(`Unable to find README table row for package: ${packageName}`);
    }

    fs.writeFileSync(readmePath, updatedContent);
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
