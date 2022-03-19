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
    public class RestaurantController : ApiController
    {
        // GET: api/Restaurant
        public IQueryable Get(string user)
        {
            return DocDBRepo.GetRestaurants(user);
        }

        // GET: api/Restaurant?user=userId&id=Id
        public IQueryable Get(string user, string id)
        {
            return DocDBRepo.GetOneRest(user, id);
        }

        // POST: api/Restaurant
        public void Post([FromBody]Restaurant newRest)
        {
            var response = DocDBRepo.CreateRestaurantAsync(newRest);

            if (response == null)
                throw new HttpResponseException(HttpStatusCode.NotImplemented);
        }


        // PUT: api/Restaurant/5
        public void Put(string id, [FromBody]Restaurant editRest)
        {
            var response = DocDBRepo.EditRestaurant(id, editRest);

            if (response == null)
                throw new HttpResponseException(HttpStatusCode.NotImplemented);
        }

        // DELETE: api/Restaurant/5
        public void Delete(string id)
        {
            var deleteMenu = DocDBRepo.DeleteRestaurant(id);
        }
    }
}
