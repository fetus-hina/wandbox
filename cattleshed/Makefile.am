SUBDIRS = src scripts test
TESTS = test/jail.sh
dist_sysconf_DATA = cattleshed.conf
sysconfddir = $(sysconfdir)/cattleshed.conf.d
cattleshed.conf: cattleshed.conf.in
	sed 's#[@]bindir[@]#$(bindir)#g' $< > $@
