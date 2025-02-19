<?php
if (extension_loaded('xhprof')) {
    xhprof_enable(
        XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY,
        [
            'ignored_functions' => ['xhprof_disable']
        ]
    );
}
