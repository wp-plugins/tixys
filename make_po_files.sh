#!/bin/bash

LANGDOMAIN='tixys'
LANGS=('de_DE')

for L in ${LANGS[*]};
do
    touch messages.po $LANGDOMAIN-$L.po # xgettext needs both files
    find -type f -iname "*.php" | \
        xgettext \
            "--from-code=utf-8" \
            "--keyword=__" \
            "--keyword=_e" \
            "--keyword=_n:1,2" \
            -j -f -
    msgmerge -N --no-wrap $LANGDOMAIN-$L.po messages.po > $L.po
    mv $L.po $LANGDOMAIN-$L.po
    rm messages.po
done
