# Magepack TLS modification notice

This image modifies Magepack 2.11.2 by creativestyle / magesuite:
https://github.com/magesuite/magepack

The only change to Magepack source is `ignoreHTTPSErrors: true` to
`ignoreHTTPSErrors: false` in `lib/generate.js`. The build-time patch script
and this notice are retained in `/usr/local/share/magepack-remediation`.
The installed, modified source and original upstream notices remain in
`/usr/local/lib/node_modules/magepack`.

Upstream npm archive:
https://registry.npmjs.org/magepack/-/magepack-2.11.2.tgz

Archive integrity:
`sha512-b0n/vW2lptBz1zwBOUAmP0J+fF0IWgji+uSjxrd5nVCD6Er/Z6+zOLSy9HyRGe9ZaqW/eM8GYMNDJF2AXkzhzA==`

Original `lib/generate.js` SHA-256:
`7d4b46180ba96597e511da62956f93c4d7db82c7ddab6ca0b94d212df6d7643e`

License discrepancy in upstream 2.11.2: `package.json` and npm metadata say
`OSL-2.0`, but the shipped `LICENSE.md` contains `OSL-3.0`.
Both are preserved without modification; this notice does not resolve or
relicense the upstream work. Obtain upstream clarification before publishing
the derivative image, and retain source, attribution, and license notices.
