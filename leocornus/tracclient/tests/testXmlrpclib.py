# testXmlrpclib.py

"""
some basic unit test cases for using xmlrpclib.
"""

import xmlrpclib

import unittest

class TestSystemMethods(unittest.TestCase):

    """
    Trying to testing the system method from the xmlrpc interface.
    """

    def setUp(self):

        self.server = xmlrpclib.ServerProxy('https://seanchen:kouling@egov.repositoryhosting.com/trac/egov_anduril/login/xmlrpc')

    def testListMethods(self):

        methods = self.server.system.listMethods()
        self.assertEquals(len(methods), 83)

# make the test suite.
def test_suite():

    suite = unittest.TestSuite()
    suite.addTest(unittest.makeSuite(TestSystemMethods))
    return suite
