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
    public class UserInfo
    {
        [Required]
        [JsonProperty(PropertyName = "userName")]
        public string UserName { get; set; }

        [Required]
        [JsonProperty(PropertyName = "password")]
        public string Password { get; set; }

    }
}