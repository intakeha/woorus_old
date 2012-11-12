%global family		woorus
%global pkgname		%{family}-www
%global pkgver		1.0
%global buildver	1	

Name:		%{pkgname}-%{pkgver}
Version:	%{pkgver}
Release:	%{buildver}%{?dist}
Summary:	The %{pkgname} package provides the web application powering Woorus.

Group:		Applications/Internet
License:	Proprietary
URL:		http://woorus.com	
Source0:	%{pkgname}-%{pkgver}.tar.bz2
BuildRoot:	%(mktemp -ud %{_tmppath}/%{name}-%{version}-%{release}-XXXXXX)

Requires:	httpd >= 2.2.3
Requires:	php >= 5.2.17
Requires:	php-mysql >= 5.2.17

%define	PackageDir	/usr/local/%{family}/%{pkgname}-%{pkgver}

%global debug_package %{nil}

%description


%prep
%setup -q -n %{name}


%build


%install
rm -rf %{buildroot}
make install DESTDIR=%{buildroot} PKGDIR=%{PackageDir}


%clean
rm -rf %{buildroot}


%files
%defattr(-,root,root,-)
%doc
%{PackageDir}



%changelog
* Sun Nov 11 2012 Kristopher Wuollett kristopher@wuollett.net - 1.0.0
- Initial RPM build
