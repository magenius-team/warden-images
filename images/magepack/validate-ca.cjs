'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');
const { X509Certificate } = require('node:crypto');

const filename = process.argv[2];
assert(filename && filename.startsWith('/'), 'NODE_EXTRA_CA_CERTS must be an absolute path');
assert(fs.statSync(filename).isFile(), 'CA path must be a regular file');
const pem = fs.readFileSync(filename, 'utf8');
assert(
    /^\s*-----BEGIN CERTIFICATE-----[\r\n]+[A-Za-z0-9+/=\r\n]+-----END CERTIFICATE-----\s*$/.test(pem),
    'Provide exactly one PEM public CA certificate, without keys or other content'
);
const cert = new X509Certificate(pem);
assert(cert.ca, 'Certificate must be a CA');
assert(Date.now() >= Date.parse(cert.validFrom), 'CA certificate is not yet valid');
assert(Date.now() <= Date.parse(cert.validTo), 'CA certificate has expired');
