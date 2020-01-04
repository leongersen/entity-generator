# Entity Generator

The Doctrine Project [will be removing support for generating entities](https://github.com/symfony/symfony-docs/issues/8893) in Doctrine 3.

Not all development workflows will fit the proposed entity-first database management strategy.

To keep supporting a database-first workflow this project implements an alternative database oriented entity generator. 

## Features
- Generate entities with type annotations, getters, setters, adders and removers;
- Supports `oneToMany` and `manyToOne` relationships, including pluralization of properties and methods.

## License
This project is licensed [MIT](https://choosealicense.com/licenses/mit/) and can be used for free in any personal or commercial project.

## Usage
```shell script
vendor/bin/entity-generator entity-generator:generate
```

### Options
- `dsn` (Defaults to the `DATABASE_URL` environment variable, so can be omitted if Doctrine is configured)
- `namespace` Namespace for the generated entities (Default `App\Entity`)
- `directory` Output directory for the generated entities (Default `src/Entity`)
- `collection` Classname for the collection type to use in the generated entities (Default `\Doctrine\Common\Collections\ArrayCollection`)

## Implementation
- A mapping is generated from an existing database using `SHOW CREATE TABLE`.
- Entity classes are rendered using a Twig template.

## Limitations
- Only provides a `\PDO` based driver and an mapper for `MySQL`-like sql dialects. 
- Annotations for (unique) indexes are not (yet) implemented
- `ManyToMany` relations are not (yet) supported ([motivation](https://stackoverflow.com/questions/18655286/doctrine-2-how-to-handle-join-tables-with-extra-columns))
- Doctrine Custom types are not supported

## Open issues
- Naming conflicts on `xyx_id` with a foreign key and field `xyx` (both become `xyz`)
- Naming conflicts on multiple foreign keys from one table to another (`xyz.abc_1_id` and `xyz.abc_2_id` both become `xyz.abcs`) 
- Blobs are not yet implemented
