
# MongoDB+

A few tiny additions to the PECL MongoDB classes. Unfortuncately, to
extend the Cursor, you also need to extend all the 'parent' classes:

* `Mongo`
* `MongoDB`
* `MongoCollection`
* and finally `MongoCursor`

## Findings ##

* MongoDB saves only arrays. No objects. Not even `DateTime`. That blows.
* MongoDB returns only Arrays. Even the results itself (0 deep) are
  arrays. Not sensible IMO. In Javascript they have to be Objects, right?
* MongoDB can store infinitely deep: a collection of Posts has Post
  records, which can have Comment records, which can have Vote and
  Tracker records, which can have etc etc. There's no depth limit.  
  It's also quite efficient (up to 2 levels at least) because of the
  `$push` `Collection->update` method.
* Querying is intuitive:
  `$Mongo->Database->Collection->find(..)->limit(..)`
* Querying is insanely complicated:
  `->find(array( '$or' => array('age' => array('$lte' => 25), 'beautiful' => true) ))`
