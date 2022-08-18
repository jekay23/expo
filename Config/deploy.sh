#!/bin/bash

git pull
cd React || exit
git pull
npm install
npm run build
