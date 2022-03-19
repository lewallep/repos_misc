using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Collections;
using System.Configuration;

using Microsoft.Azure.Documents;
using Microsoft.Azure.Documents.Client;
using Microsoft.Azure.Documents.Linq;

using Newtonsoft.Json;
using System.ComponentModel.DataAnnotations;

namespace fpDBSeed
{
    class Program
    {
        private static readonly string url = ConfigurationManager.AppSettings["EndPointUrl"];
        private static readonly string auth = ConfigurationManager.AppSettings["AuthKey"];

        static void Main(string[] args)
        {
            try
            {
                CreateDatabase().Wait();
            }
            catch (DocumentClientException de)
            {
                Exception baseException = de.GetBaseException();
                Console.WriteLine("There was an erro somewhere {0}.", de.StatusCode);
            }
            catch (Exception e)
            {
                Exception baseException = e.GetBaseException();
                Console.WriteLine("Error:", e.Message);
            }
            finally
            {
                Console.WriteLine("End of Demo, press any key to exit.");
                Console.ReadKey();
            }
        }

        private static async Task CreateDatabase()
        {
            var client = new DocumentClient(new Uri(url), auth);

            // Create the database if it does not exist.
            Database database = client.CreateDatabaseQuery().Where(db => db.Id == "fProj")
                .AsEnumerable().FirstOrDefault();

            if (database == null)
            {
                database = await client.CreateDatabaseAsync(new Database { Id = "fProj" });
            }
            else
            {
                Console.WriteLine("Your Database existed previously");
            }

            // Create the Document Collection if it does not exist.
            DocumentCollection col = client.CreateDocumentCollectionQuery
                (database.CollectionsLink)
                .Where(c => c.Id == "fpDocs")
                .AsEnumerable().FirstOrDefault();

            if (col == null)
            {
                col = await client.CreateDocumentCollectionAsync
                    (database.CollectionsLink, 
                    new DocumentCollection { Id = "fpDocs" },
                    new RequestOptions { OfferType = "S1" });
            }
            else
            {
                Console.WriteLine("Yo, you already had that collection");
            }

            // Create the first document
            Document doc = client.CreateDocumentQuery(col.DocumentsLink)
                .Where(d => d.Id == "1").AsEnumerable().FirstOrDefault();

            if (doc == null)
            {
                Restaurant doc1 = new Restaurant
                {
                    Id = "1",
                    RName = "Duck's Country Bunker",
                    UserId = "user1",
                    FName = "Phil",
                    LName = "McLovin",
                    MenuItems = new MenuItem[] { 
                        new MenuItem { Id = "1", ItemName = "Small Fries", Calories = 333, Price = .99M },
                        new MenuItem { Id = "2", ItemName = "Medium Fries", Calories = 444, Price = 1.99M },
                        new MenuItem { Id = "3", ItemName = "Large Fries", Calories = 555, Price = 2.99M },
                        new MenuItem { Id = "4", ItemName = "Small Beer", Calories = 5, Price = .99M },
                        new MenuItem { Id = "5", ItemName = "Medium Beer", Calories = 10, Price = 2.00M },
                        new MenuItem { Id = "6", ItemName = "Normal Sized Beer", Calories = 300, Price = 3.00M },
                        new MenuItem { Id = "7", ItemName = "Coffee", Calories = 50, Price = 1.00M }
                    },
                    Address = new Address
                    {
                        Id = "1",
                        StreetNumber = "1235 SE Brooklyn St.",
                        City = "Corvallis",
                        State = "Oregon",
                        ZipCode = "99775"
                    }
                };

                await client.CreateDocumentAsync(col.DocumentsLink, doc1);
            }

            // Create the second document
            doc = client.CreateDocumentQuery(col.DocumentsLink)
                .Where(d => d.Id == "2").AsEnumerable().FirstOrDefault();

            if (doc == null)
            {
                Restaurant doc2 = new Restaurant
                {
                    Id = "2",
                    RName = "Obi One",
                    UserId = "user1",
                    FName = "Allen",
                    LName = "Jones",
                    MenuItems = new MenuItem[] { 
                        new MenuItem { Id = "1", ItemName = "Cheeseburger", Calories = 75, Price = .99M },
                        new MenuItem { Id = "2", ItemName = "Hamburger", Calories = 50, Price = 1M },
                        new MenuItem { Id = "3", ItemName = "Regular Onion Rings", Calories = 100, Price = 1.50M },
                        new MenuItem { Id = "4", ItemName = "Large Onion Rings", Calories = 300, Price = 2M }
                    },
                    Address = new Address
                    {
                        Id = "2",
                        StreetNumber = "4949 N Broadway Ave",
                        City = "Eugene",
                        State = "Oregon",
                        ZipCode = "96709"
                    }
                };

                await client.CreateDocumentAsync(col.DocumentsLink, doc2);
            }

            doc = client.CreateDocumentQuery(col.DocumentsLink)
                .Where(d => d.Id == "3").AsEnumerable().FirstOrDefault();

            if (doc == null)
            {
                Restaurant doc3 = new Restaurant
                {
                    Id = "3",
                    RName = "Classy Chicken",
                    UserId = "user2",
                    FName = "Ash",
                    LName = "Awesome",
                    MenuItems = new MenuItem[] { 
                        new MenuItem { Id = "1", ItemName = "Small Chicken Dinner", Calories = 1111, Price = 11M },
                        new MenuItem { Id = "2", ItemName = "Medium Chicken Dinner", Calories = 2222, Price = 22M },
                        new MenuItem { Id = "3", ItemName = "Large Chicken Dinner", Calories = 3333, Price = 33M },
                        new MenuItem { Id = "4", ItemName = "Freedom Sized Chicken Dinner", Calories = 4444, Price = 44M },
                        new MenuItem { Id = "5", ItemName = "Small Chicken Wings", Calories = 500, Price = 5M },
                        new MenuItem { Id = "6", ItemName = "Medium Chicken Wings", Calories = 1000, Price = 7M },
                        new MenuItem { Id = "7", ItemName = "Large Chicken Wings", Calories = 1500, Price = 10M }
                    },
                    Address = new Address
                    {
                        Id = "3",
                        StreetNumber = "500 Midtown Ave",
                        City = "Los Angeles",
                        State = "California",
                        ZipCode = "90210"
                    }
                };

                await client.CreateDocumentAsync(col.DocumentsLink, doc3);
            }

            doc = client.CreateDocumentQuery(col.DocumentsLink)
                .Where(d => d.Id == "4").AsEnumerable().FirstOrDefault();

            if (doc == null)
            {
                Restaurant doc4 = new Restaurant
                {
                    Id = "4",
                    RName = "Four Stars",
                    UserId = "user2",
                    FName = "Ash",
                    LName = "Awesome",
                    MenuItems = new MenuItem[] { 
                        new MenuItem { Id = "1", ItemName = "Pretentious Meal #1", Calories = 1, Price = 100M },
                        new MenuItem { Id = "2", ItemName = "Pretentious Meal #2", Calories = 2, Price = 100M },
                        new MenuItem { Id = "3", ItemName = "Pretentious Meal #3", Calories = 3, Price = 300M },
                        new MenuItem { Id = "4", ItemName = "Small Gerbil Food", Calories = 4, Price = 400M },
                        new MenuItem { Id = "5", ItemName = "Medium Gerbil Food", Calories = 5, Price = 50M },
                        new MenuItem { Id = "6", ItemName = "Large Gerbil Food", Calories = 6, Price = 60M }
                    },
                    Address = new Address
                    {
                        Id = "4",
                        StreetNumber = "3001 SE Belmont St.",
                        City = "Portland",
                        State = "Oregon",
                        ZipCode = "97214"
                    }
                };

                await client.CreateDocumentAsync(col.DocumentsLink, doc4);
            }


            var items = client.CreateDocumentQuery(col.DocumentsLink, "SELECT r.id, r.rName FROM Restaurant r");

            // Query and check what I have entered is there.
            foreach(var rest in items)
            {
                Console.WriteLine(rest);
            }
            
            // Begin seeding of the collection with users
            Document user = client.CreateDocumentQuery(col.DocumentsLink).Where(d => d.Id == "user1")
             .AsEnumerable().FirstOrDefault();

            if (user == null)
            {
                Users user1 = new Users
                {
                    Id = "user1",
                    UserName = "King Phil",
                    Password = "BestEver"
                };

                await client.CreateDocumentAsync(col.DocumentsLink, user1);
            }

            user = client.CreateDocumentQuery(col.DocumentsLink).Where(d => d.Id == "user2")
                .AsEnumerable().FirstOrDefault();

            if (user == null)
            {
                Users user2 = new Users
                {
                    Id = "user2",
                    UserName = "Nobody",
                    Password = "noOneCares"
                };

                await client.CreateDocumentAsync(col.DocumentsLink, user2);
            }

            // TODO: Try to query a user assosciated with a restaurant as a separate document.
            /*
            */

            //await client.DeleteDatabaseAsync(database.SelfLink);

            // Dispose of the client connection.
            client.Dispose();
        }
    }
}
