using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using System.Collections;
using System.Configuration;
using System.ComponentModel.DataAnnotations;

using Newtonsoft.Json;

namespace fpDBSeed
{
    internal sealed class Restaurant
    {
        [Required]
        [JsonProperty(PropertyName = "id")]
        public string Id { get; set; }

        [Required]
        [JsonProperty(PropertyName = "rName")]
        public string RName { get; set; }

        [Required]
        [JsonProperty(PropertyName = "userId")]
        public string UserId { get; set; }

        [Required]
        [JsonProperty(PropertyName = "fName")]
        public string FName { get; set; }

        [Required]
        [JsonProperty(PropertyName = "lName")]
        public string LName { get; set; }

        [JsonProperty(PropertyName = "menuItems")]
        public MenuItem[] MenuItems { get; set; }

        [Required]
        [JsonProperty(PropertyName = "address")]
        public Address Address { get; set; }
    }

    internal sealed class MenuItem
    {
        [Required]
        [JsonProperty(PropertyName = "id")]
        public string Id { get; set; }

        [Required]
        [JsonProperty(PropertyName = "itemName")]
        public string ItemName { get; set; }

        [Required]
        [JsonProperty(PropertyName = "calories")]
        public int Calories { get; set; }

        [Required]
        [JsonProperty(PropertyName = "price")]
        public decimal Price { get; set; }
    }

    internal sealed class Address
    {
        [Required]
        [JsonProperty(PropertyName = "id")]
        public string Id { get; set; }

        [Required]
        [JsonProperty(PropertyName = "streetNumber")]
        public string StreetNumber { get; set; }

        [JsonProperty(PropertyName = "city")]
        public string City { get; set; }

        [JsonProperty(PropertyName = "state")]
        public string State { get; set; }

        [JsonProperty(PropertyName = "zipCode")]
        public string ZipCode { get; set; }
    }
}
