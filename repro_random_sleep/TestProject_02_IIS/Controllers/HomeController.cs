using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using System.Threading;

namespace TestProject_02_IIS.Controllers
{
    public class HomeController : Controller
    {
        public ActionResult Index()
        {
            // methodOne();
            // methodTwo();
            // methodThree();
            RandomLengthSleep();

            return View();
        }

        public ActionResult About()
        {
            ViewBag.Message = "Your application description page.";

            return View();
        }

        public ActionResult Contact()
        {
            ViewBag.Message = "Your contact page.";

            return View();
        }

        // Soon to be deprecated
        public void MethodOne()
        {
            Thread.Sleep(1000);
        }

        // Soon to be deprecated
        public void MethodTwo()
        {
            Thread.Sleep(1000);
        }

        // Deprecated.
        public void MethodThree()
        {
            Thread.Sleep(1000);
        }

        public void RandomLengthSleep()
        {
            int sleepTime = 100;

            Random randomOne = new Random();

            sleepTime = randomOne.Next(100, 120000);

            Thread.Sleep(sleepTime);
        }
    }
}