#!/bin/bash

set -eu

CFLAGS="-mtune=native -march=native -O3"
CXXFLAGS=$CFLAGS

pushd cattleshed
  autoreconf -i
  automake
  ./configure --prefix=/opt/wandbox/cattleshed
  make
popd

pushd kennel2
  ./autogen.sh
  ./configure --prefix=/opt/wandbox/wandbox --with-cppcms=/opt/wandbox/depends/cppcms --with-cppdb=/opt/wandbox/depends/cppdb
  make
popd

sudo /home/wandbox/wandbox-sudo.sh
