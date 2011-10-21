
# testFunctionalDoctest.py

"""
make the test suite for the doctest in text file.
"""

from unittest import TestSuite
from doctest import DocFileSuite

__author__ = "Sean Chen"
__email__ = "sean.chen@leocorn.com"

# this the entry point for unit test.
def test_suite():

   suite = TestSuite()
   suite.addTest(DocFileSuite('tests/basicXmlrpc.txt',
                              package='leocornus.tracclient'))
   suite.addTest(DocFileSuite('tests/basicConfigparser.txt',
                              package='leocornus.tracclient'))
   return suite
