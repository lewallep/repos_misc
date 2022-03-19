using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

using System.Data.Entity;
using Microsoft.Azure.Documents;
using Newtonsoft.Json;
using System.ComponentModel.DataAnnotations;

namespace FinalProj.Models
{
    public class Restaurant
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

    public class MenuItem
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
    public class Address
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