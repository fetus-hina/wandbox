#!/bin/bash

set -eu

CFLAGS="-mtune=native -march=native -O3"
CXXFLAGS=$CFLAGS

pushd cattleshed
  autoreconf -i
  automake
  ./configure --prefix=/opt/wandbox
  make
  sudo make install
popd

pushd kennel2
  ./autogen.sh
  ./configure --prefix=/opt/wandbox --with-cppcms=/opt/cppcms --with-cppdb=/opt/cppdb
  make
  sudo make install
popd

sudo systemctl restart wandbox-cattleshed
