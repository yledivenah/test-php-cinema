# TestWe (recommended time: 2h00-3h00)

TestWe has a movie database designed for a previous application. We want to develop a new application that will allow interaction with this database via REST APIs. This application must be written in Symfony.

Prerequisites: a server with Symfony installed and an SQL database where to import the provided files.

##First part

The product team did not have time to make precise specifications, so it will be necessary to use "common sense" in the development of the application. The only clearly identified requirements are:
- Read operations on the different objects must be possible via public APIs
- Write operations (adding films or actors) must require authentication via an account or a key
- TestWe being convinced of the commercial potential of this project, we expect to have both large volumes of data and large peak loads. The performance aspects are therefore particularly important.

##Second part

After a demo made to a prospective customer, the sales team added a critical need: being able to include the URL of the movie's poster whenever it is available!

For this, it is possible to use the following IMDB API: https://rapidapi.com/apidojo/api/imdb8/ (test account: login: cedric.dejacquelot@testwe.eu / password: OK65:mu80:ni97:iw57) by searching on the title of the movie. If there are multiple results, the first one will do.

On the other hand, the management of TestWe noted that these APIs are paying if used in large amounts, this will have to be taken into account.

##Submission and other instructions

The finished project is to be submited as a git repository. If the SQL structure has changed, include the modified scripts. The goal is not necessarily to finish everything, but to do enough to serve as a basis for discussion during the technical interview.