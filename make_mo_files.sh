#!/bin/bash

for i in $(ls *.po | sed 's|.po||g'); do
    msgfmt --statistics -vco $i.mo $i.po
done
