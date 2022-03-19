using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Web.Http;

using Microsoft.Azure.Documents;
using Microsoft.Azure.Documents.Client;
using Microsoft.Azure.Documents.Linq;

using System.Threading.Tasks;
using System.Configuration;

using FinalProj.Models;

namespace FinalProj.Controllers
{
    public class UserController : ApiController
    {
        // GET: api/User
        public IQueryable Get(string userName, string password)
        {
            return DocDBRepo.GetUser(userName, password);
        }

        // POST: api/User?user=username&id=userId
        public void Post([FromBody]UserInfo newUser)
        {
            var response = DocDBRepo.CreateUserAsync(newUser);

            if (response == null)
                throw new HttpResponseException(HttpStatusCode.NotImplemented);
        }
    }
}
