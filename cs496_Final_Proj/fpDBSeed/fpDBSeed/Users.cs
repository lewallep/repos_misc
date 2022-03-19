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
    internal sealed class Users
    {
        [Required]
        [JsonProperty(PropertyName = "id")]
        public string Id { get; set; }

        [Required]
        [JsonProperty(PropertyName = "userName")]
        public string UserName { get; set; }

        [Required]
        [JsonProperty(PropertyName = "password")]
        public string Password { get; set; }
    }
}
