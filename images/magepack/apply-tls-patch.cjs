'use strict';

// Build-time only. Reject unreviewed upstream changes rather than guessing.
const assert = require('node:assert/strict');
const { createHash } = require('node:crypto');
const fs = require('node:fs');
const path = require('node:path');

const root = process.argv[2];
assert(root, 'Magepack installation path is required');
const metadata = JSON.parse(fs.readFileSync(path.join(root, 'package.json'), 'utf8'));
assert.equal(metadata.version, '2.11.2', 'Review the TLS patch before changing Magepack');
assert.equal(metadata.license, 'OSL-2.0', 'Upstream license metadata changed');
assert(fs.statSync(path.join(root, 'LICENSE.md')).isFile(), 'Keep the upstream license');
const puppeteer = require.resolve('puppeteer/package.json', { paths: [root] });
assert.equal(JSON.parse(fs.readFileSync(puppeteer, 'utf8')).version, '2.1.1');

const filename = path.join(root, 'lib/generate.js');
const source = fs.readFileSync(filename, 'utf8');
assert.equal(
    createHash('sha256').update(source).digest('hex'),
    '7d4b46180ba96597e511da62956f93c4d7db82c7ddab6ca0b94d212df6d7643e',
    'Unrecognized Magepack generate.js; refusing to patch'
);
const before = '        ignoreHTTPSErrors: true,';
const after = '        ignoreHTTPSErrors: false,';
assert.equal(source.split(before).length, 2, 'Expected exactly one TLS option');
fs.writeFileSync(filename, source.replace(before, after));
