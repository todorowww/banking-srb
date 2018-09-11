Banking SRB ![TravisCI build](https://api.travis-ci.org/todorowww/banking-srb.svg?branch=master)
=================================

This library contains useful banking functions in use by banks in Serbia.
By the order of the Governor of National Bank of Serbia, account numbers are structured in the following manner:

- 3 digits represent bank ID Fixed value, assigned by the NBS. List, as of 16 April 2018 [PDF](https://www.nbs.rs/internet/latinica/20/plp/pu_jedinstveni_id_brojevi.pdf)
- 13 digits represent account number, bank manages this number
- 2 digits represent checksum number, calculated using ISO 7064 MOD 97-10

When payment reference numbers are generated, checksum goes to the front of the number, It is calculated using the same ISO 7064 MOD 97-10 algorithm as bank account number checksum.
Reference number must consist only of digits, and if it should include a letter, that letter is transformed into a number, to generate checksum.
Conversion follows this pattern: A=10, B=11, C=12, ..., Y = 34, Z = 35.

## Installation ##

Using Composer, install the latest version of this library by issuing this command

	composer require todorowww/banking-srb

If you wish to use clean install, without Composer, you can clone this repo:

	git clone https://github.com/todorowww/banking-srb.git
