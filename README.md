# quizz_api
An API that provides questions about web development in general

## Class documentation (*CRUD*)
The Crud class is an abstract class that serves a base class for implementing CRUD (Create, Read, Update, Delete) operations in PHP using PDO (PHP Data Objects). The class defines the basic CRUD operations that can be used to interact with a database.

## Class documentation (*CONTROLLER*)
The Controller class is an abstract Controller class belonging to the App\controller namespace. This class serves as a base for creating controllers that handle incoming HTTP requests.

### Class Structure (*Crud*)
The Crud class is defined in the namespace App\crud. The class is abstract and has the following structure:

The class has the following properties:

- $column: An array that contains the names of the columns in the database table.
- $table: A string that contains the name of the database table.
- $pdo: An instance of the PDO class that is used to connect to the database.
The class has the following methods:

- __construct(PDO $pdo): Constructor method that accepts an instance of the PDO class and initializes the $pdo property.
- retrieveAll(): array: Method that retrieves all records from the database table.
- retrieveOne(int $id): ?array: Method that retrieves a single record from the database table based on the provided ID.
- createItem(array $data): int: Method that creates a new record in the database table and returns the ID of the new record.
- updateItem(array $data, int $id): bool: Method that updates an existing record in the database table based on the provided ID and returns true if the update was successful.
- deleteItem(int $id): bool: Method that deletes a record from the database table based on the provided ID and returns true if the delete was successful.

### Class Structure (*Controller*)

The Controller class has the following properties:

- protected const ACCEPTED_COLLECTION_METHODS: A constant that defines an array containing the accepted HTTP methods for collections. In this case, it is GET and POST.
- protected const ACCEPTED_RESOURCE_METHODS: A constant that defines an array containing the accepted HTTP methods for resources. In this case, it is GET, PUT, and DELETE.
- protected Crud $crud: A Crud object that is used to perform CRUD (Create, Read, Update, Delete) operations on the database.
- protected PDO $pdo: A PDO object that represents the connection to the database.
- protected string $uri: The URI query string sent in the HTTP request.
- protected string $method: The HTTP method used for the request.
- protected array $uriParts: An array containing the different parts of the URI query string.
- protected int $uriPartsCount: The number of parts in the URI query string.


### Method Details
retrieveAll(): array
This method retrieves all records from the database table and returns an array of records.

If there are no records in the table, an EmptyParameterException is thrown.

retrieveOne(int $id): ?array
This method retrieves a single record from the database table based on the provided ID and returns an array containing the record.

If the specified ID is not valid, an InvalidArgumentException is thrown.

If the specified ID does not exist in the database, an IdNotFoundException is thrown.

createItem(array $data): int
This method creates a new record in the database table using the provided data and returns the ID of the new record.

If any of the required parameters are missing or empty, an InvalidArgumentException or an EmptyParameterException is thrown, respectively.

updateItem(array $data, int $id): bool
This method updates an existing record in the database table based on the provided ID and returns true if the update was successful.

If any of the required parameters are missing or empty, an InvalidArgumentException or an EmptyParameterException is thrown, respectively.

If the specified ID is not valid, an InvalidArgumentException is thrown.

deleteItem(int $id): bool
This method deletes a record from the database table based on the provided ID and returns true if the delete was successful.
