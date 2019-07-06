#!/bin/bash
cd "$(dirname "$0")"
mv cmake-build-debug/*solver bin/
ls bin/*solver | xargs -I A echo "echo A && A" | bash
