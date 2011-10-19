from setuptools import setup, find_packages
import sys, os

version = '0.1'

setup(name='leocornus.tracclient',
      version=version,
      description="Python XML-RPC client to Trac",
      long_description="""\
easy-to-use XML-RPC cleint to access Trac""",
      classifiers=[], # Get strings from http://pypi.python.org/pypi?%3Aaction=list_classifiers
      keywords='xmlrpc trac',
      author='Sean Chen',
      author_email='sean.chen@leocorn.com',
      url='',
      license='GPL',
      packages=find_packages(exclude=['ez_setup', 'examples', 'tests']),
      include_package_data=True,
      zip_safe=False,
      install_requires=[
          # -*- Extra requirements: -*-
      ],
      entry_points="""
      # -*- Entry points: -*-
      """,
      )
