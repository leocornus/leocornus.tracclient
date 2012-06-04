# testXmlrpclib.py

"""
some basic unit test cases for using xmlrpclib.
"""

import xmlrpclib
import os
try:
    import configparser
except ImportError:
    import ConfigParser as configparser

import unittest

class TestSystemMethods(unittest.TestCase):

    """
    Trying to testing the system method from the xmlrpc interface.
    """

    def setUp(self):

        """
        Assume we config the trac server info in a config file 
        ~/.leocorn.cfg
        """

        traccfg = configparser.ConfigParser()
        traccfg.read(os.path.expanduser('~/.leocorn.cfg'))
        self.server = xmlrpclib.ServerProxy('https://' +
            traccfg.get('testing', 'username') + ':' + 
            traccfg.get('testing', 'password') + '@' + 
            traccfg.get('testing', 'tracxmlrpc'))

    def testListMethods(self):

        methods = self.server.system.listMethods()
        self.assertEquals(len(methods), 83)

# make the test suite.
def test_suite():

    suite = unittest.TestSuite()
    suite.addTest(unittest.makeSuite(TestSystemMethods))
    return suite
