#!conding=utf-8

from nose.tools import assert_equal

class Person:
  age = 0

  def __init__(self, name):
    self.name = name

  def setAge(self, age):
    self.age = age

  def getAge(self):
    return self.age

  def printName(self):
    print(self.name)



def testPerson():
  p = Person('tom')
  p.printName()

  p.setAge(20)
  print(p.getAge())

  assert_equal(p.name, 'jack')
  assert_equal(p.age, 20)





