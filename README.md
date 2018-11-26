ORM persistence support
=======================

Master: [![Build Status](https://travis-ci.com/rindow/rindow-persistence-orm.png?branch=master)](https://travis-ci.com/rindow/rindow-persistence-orm)

This module defines a Java Persistence API (JPA) -like ORM interface and aims to be a vendor-independent ORM. The goal is to register interfaces defined here in interop-phpobjects in the future.

The ORM implementation is not included.

The following functions are defined.

- Entitiy-Manager running on transaction manager.
- Query criteria builder.
- ORM under the DAO Repository

Currently annotations are not implemented.
