const fs = require('fs');
const axios = require('axios');

const githubToken = process.env.GITHUB_TOKEN;
const packageName = process.env.PACKAGE_NAME;

const axiosInstance = axios.create({
    baseURL: 'https://api.github.com',
    headers: {
        'Authorization': `token ${githubToken}`,
        'Accept': 'application/vnd.github.v3+json'
    }
});

async function getLastPage() {
    try {
        const response = await axiosInstance.get(`/orgs/magenius-team/packages/container/${packageName.toLowerCase()}/versions?per_page=100`);
        const linkHeader = response.headers.link || '';
        const lastPageMatch = linkHeader && linkHeader.match(/page=(\d+)>; rel="last"/)[1] || 1;
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
            const response = await axiosInstance.get(`/orgs/magenius-team/packages/container/${packageName}/versions?per_page=100&page=${lastPage}`);
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
        console.error('Error fetching untagged versions:', error);
        return [];
    }
}

function updateReadme(versions) {
    const readmePath = 'README.md';
    const readmeContent = fs.readFileSync(readmePath, 'utf8');
    const versionList = versions.join(', ');

    const updatedContent = readmeContent.replace(
        new RegExp(`(\\| ${packageName.trim()}\\s* \\|).*`),
        `$1 ${versionList} |`
    );

    console.log(updatedContent);

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
