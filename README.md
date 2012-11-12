Woorus WWW
==========

Developer Setup
---------------

### Required Software

Install Centos-6.3 on your workstation or a virtual machine.  And then run:

    sudo yum install rpmdevtools rpmlint
    rpmdev-setuptree

### Build the Package

    cd PACKAGE 
    make rpm

A woorus-PACKAGE-VERSION.rpm should now be present in the PACKAGE directory.
