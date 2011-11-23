
## MongoDB+

A few tiny additions to the PECL MongoDB classes. Unfortuncately, to
extend the Cursor, you also need to extend all the 'parent' classes:

* `Mongo`
* `MongoDB`
* `MongoCollection`
* and finally `MongoCursor`
