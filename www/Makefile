# Default Target
all::

.PHONY: clean install

# Protect against environment variables
SPECFILE=
NAME=
VERSION=
RELEASE=
PACKAGE=
RPMBUILD=
RPM=

# Pull info from spec file 
SPECFILE := woorus-www.spec
NAME := woorus-www
VERSION := 1.0
RELEASE := 1$(shell rpm --eval '%{dist}')

# Build environment
RPMBUILD := $(HOME)/rpmbuild

RPM := $(NAME)-$(VERSION)-$(RELEASE).rpm 

$(RPM): 
	tar --transform='s,^,/$(NAME)-$(VERSION)/,' -jcf \
		$(RPMBUILD)/SOURCES/$(NAME)-$(VERSION).tar.bz2 Makefile htdocs
	cp $(SPECFILE) $(RPMBUILD)/SPECS
	rpmbuild -bb $(RPMBUILD)/SPECS/$(SPECFILE)
	find $(RPMBUILD)/RPMS -name '$(NAME)-$(VERSION)*.rpm' \
		-exec cp '{}' . \;

rpm: $(RPM)

install:
	mkdir -p $(DESTDIR)$(PKGDIR)/www
	cp -r htdocs/* $(DESTDIR)$(PKGDIR)/www

clean:
	rm -f *.rpm
