#!/bin/bash

cd ..
git pull
cd React || exit
git pull
npm run build
