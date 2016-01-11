SUBDIRS = src scripts test
TESTS = test/jail.sh
dist_sysconf_DATA = cattleshed.conf
sysconfddir = $(sysconfdir)/cattleshed.conf.d
cattleshed.conf.in: cattleshed.conf.in.d/base.conf cattleshed.conf.in.d/jail.chroot.php
	scl enable php70 './mkconfin'
cattleshed.conf: cattleshed.conf.in
	sed 's#[@]bindir[@]#$(bindir)#g' $< > $@
