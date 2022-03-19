using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

using Microsoft.Azure.Documents;
using Microsoft.Azure.Documents.Client;
using Microsoft.Azure.Documents.Linq;
using System.Configuration;
using System.Linq.Expressions;
using System.Threading.Tasks;

using FinalProj.Models;

namespace FinalProj
{
    public class DocDBRepo
    {
        public static IQueryable<dynamic> GetAllUsers()
        {
            return Client.CreateDocumentQuery(Collection.DocumentsLink, "SELECT u.userName, u.password FROM UserInfo u");
        }

        // Get a single user information if the userName and password match.
        public static IQueryable<dynamic> GetUser(string userName, string password)
        {
            var querySpec = new SqlQuerySpec()
            {
                QueryText = "SELECT u.userName, u.password FROM UserInfo u WHERE u.userName = @username AND u.password = @password",
                Parameters = new SqlParameterCollection {
                    new SqlParameter { Name = "@username", Value = userName },
                    new SqlParameter { Name = "@password", Value = password }
                }
            };

            return Client.CreateDocumentQuery<dynamic>(Collection.DocumentsLink, querySpec);
        }

        // POST request to make a new User
        public static async Task<dynamic> CreateUserAsync(UserInfo newUser)
        {
            return await Client.CreateDocumentAsync(Collection.SelfLink, newUser);
        }


        // This will be expanded to pass in a user and get only those users entries.
        public static IQueryable<dynamic> GetRestaurants(string user)
        {
            var querySpec = new SqlQuerySpec()
            {
                QueryText = "SELECT * FROM Restaurant r WHERE r.userId = @userId",
                Parameters = new SqlParameterCollection{
                    new SqlParameter { Name = "@userId", Value = user }
                }
            };

            return Client.CreateDocumentQuery<dynamic>(Collection.DocumentsLink, querySpec);
            //return Client.CreateDocumentQuery(Collection.DocumentsLink, "SELECT * FROM Restaurant");
        }

        public static IQueryable<dynamic> GetOneRest(string user, string Id)
        {
            var querySpec = new SqlQuerySpec()
            {
                QueryText = "SELECT * FROM Restaurant r WHERE r.id = @id",
                Parameters = new SqlParameterCollection {
                    new SqlParameter { Name = "@id", Value = Id }
                }
            };

            return Client.CreateDocumentQuery<dynamic>(Collection.DocumentsLink, querySpec);
        }

        // POST request to make a new Restaurant
        public static async Task<dynamic> CreateRestaurantAsync(Restaurant rest)
        {
            return await Client.CreateDocumentAsync(Collection.SelfLink, rest);
        }

        // PUT request to edit a Restaurant.
        public static async Task<Document> EditRestaurant(string id, Restaurant item)
        {
            Document doc = GetDocument(id);
            return await Client.ReplaceDocumentAsync(doc.SelfLink, item);
        }

        // DELETE Deletes a single restaurant.
        public static async Task<Document> DeleteRestaurant(string id)
        {
            Document doc = GetDocument(id);
            return await Client.DeleteDocumentAsync(doc.SelfLink);
        }

        // This is a helper statement for the DELETE and PUT statements.
        public static Document GetDocument(string id)
        {
            return Client.CreateDocumentQuery(Collection.DocumentsLink)
                .Where(d => d.Id == id)
                .AsEnumerable()
                .FirstOrDefault();
        }


        // Begin section of document client initialization

        //Use the Database if it exists, if not create a new Database
        private static Database ReadOrCreateDatabase()
        {
            var db = Client.CreateDatabaseQuery()
                            .Where(d => d.Id == DatabaseId)
                            .AsEnumerable()
                            .FirstOrDefault();

            if (db == null)
            {
                db = Client.CreateDatabaseAsync(new Database { Id = DatabaseId }).Result;
            }

            return db;
        }

        //Use the DocumentCollection if it exists, if not create a new Collection
        private static DocumentCollection ReadOrCreateCollection(string databaseLink)
        {
            var col = Client.CreateDocumentCollectionQuery(databaseLink)
                              .Where(c => c.Id == CollectionId)
                              .AsEnumerable()
                              .FirstOrDefault();

            if (col == null)
            {
                var collectionSpec = new DocumentCollection { Id = CollectionId };
                var requestOptions = new RequestOptions { OfferType = "S1" };

                col = Client.CreateDocumentCollectionAsync(databaseLink, collectionSpec, requestOptions).Result;
            }

            return col;
        }

        //Expose the "database" value from configuration as a property for internal use
        private static string databaseId;
        private static String DatabaseId
        {
            get
            {
                if (string.IsNullOrEmpty(databaseId))
                {
                    databaseId = ConfigurationManager.AppSettings["database"];
                }

                return databaseId;
            }
        }

        //Expose the "collection" value from configuration as a property for internal use
        private static string collectionId;
        private static String CollectionId
        {
            get
            {
                if (string.IsNullOrEmpty(collectionId))
                {
                    collectionId = ConfigurationManager.AppSettings["dbCollection"];
                }

                return collectionId;
            }
        }

        //Use the ReadOrCreateDatabase function to get a reference to the database.
        private static Database database;
        private static Database Database
        {
            get
            {
                if (database == null)
                {
                    database = ReadOrCreateDatabase();
                }

                return database;
            }
        }

        //Use the ReadOrCreateCollection function to get a reference to the collection.
        private static DocumentCollection collection;
        private static DocumentCollection Collection
        {
            get
            {
                if (collection == null)
                {
                    collection = ReadOrCreateCollection(Database.SelfLink);
                }

                return collection;
            }
        }

        //This property establishes a new connection to DocumentDB the first time it is used, 
        //and then reuses this instance for the duration of the application avoiding the
        //overhead of instantiating a new instance of DocumentClient with each request
        private static DocumentClient client;
        private static DocumentClient Client
        {
            get
            {
                if (client == null)
                {
                    string endpoint = ConfigurationManager.AppSettings["EndPointUrl"];
                    string authKey = ConfigurationManager.AppSettings["AuthKey"];
                    Uri endpointUri = new Uri(endpoint);
                    client = new DocumentClient(endpointUri, authKey);
                }

                return client;
            }
        }
    }
}