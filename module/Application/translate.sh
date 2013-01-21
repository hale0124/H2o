#!/bin/bash
# TRANSLATE .phtml module files
# ------------------------------
LG="cs_CZ"
L_DIR="`dirname $0`/languages/$LG.po"
touch "$L_DIR"
#echo $L_DIR
xgettext --from-code=UTF-8 --force-po -j -o "$L_DIR" --keyword=translate --language=PHP `find . -type f -name *.phtml`
