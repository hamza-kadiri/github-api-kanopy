# github-api-kanopy

Kanopy exercise : a website that lists the commits in a given Github repository

## Installation

Simply run `docker-compose up` inside the repository and the website will be available at `localhost:8000`

## My approach

Since the goal of the exercise was to focus on PHP, I decided to use PHP only. Also, I didn't see the point of splitting the website into a front-end and a back-end in this case, because the back-end routes would have been the same as Github's API.  
I have also chosen to work without any database, as I think it would have complexified the website without providing a real benefit.

I began by gathering the commits associated to Linux repository, then I chose to keep the useful informations in each commit and get rid of the rest via the `$format_commits` function. Then I created my commit tiles based on the Bulma CSS Framework.
The tiles display the committer's name, login, and avatar, the commit's truncated message and id (sha) and the time elapsed since the commit was submitted.

The second view is accessed by clicking on the commit's sha. It shows the full commit message, and the committer informations.
A short sentence mentioning the number of additions and deletions is displayed under it. The file patches are then displayed, the lines have different colors depending if it is an addition, a deletion or the beginning of a hunk. Since these patches can be pretty long, I chose to wrap them into an accordion. For this purpose I did not want to bother with extra javascript and I used the accordion component of the Bulma Extensions Framework.

I had the time to add two bonus features :

- Filter by committer
- Research a repository

### Filter by comitter:

I created an array containing the name of the committers of the listed commits to put them on the dropdown options. I didn't make any extra call to the API since this information was already provided by the github commits API. I didn't want to make a call to the api each time the user changes the filters, so I decided to perform ajax requests with jQuery. When the value in the dropdown changes, a POST request is sent with the new value of the filter, the commits array is then filtered keeping only the desired commiter data and a new html code is returned. This code is then displayed using javascript.

### Research a repository:

The user can type a query in the search bar, a window will open on the right. He can then choose the repository he would like to see. This was done with the same way ad the filter feature, using ajax requests. The right window is the Quickview component from the Bulma extensions framework.

## Possible improvements :

#### Github authentication

A github authentication using the OAuth PHP Extension could be implemented, It would allow the user to perform more than 60 calls to the API per hour.

#### PHP Error handling

Error are not handled properly, so when an API request fails, the user does not really know the reason because the displayed messages are not explicit.

#### Search bar :

At the moment, the search bar has no validators, it also allows empty querys and querys with spaces or special characters are not correctly parsed.

#### Parameters of GET Requests :

Currently some parameters of the GET Requests are too long, it makes the link very long and pretty hard to paste. Also, Some API links are used as GET parameters and this should be changed.

## Time spent on the project :

This project was my first experience with PHP and jQuery, I spent a couple of hours in documentation.

Except that, I spent a total of 6-7 hours in the project. I got used pretty fast to the PHP syntax and the Bulma CSS Framework made the front fast to code.  
 The detail is as follows :

- Initialization of the project and 1st view : 1 hour
- Commit view with patches colors (took me a lot of time): 2-3 hours
- Filter feature : 1 hour
- Search feature : 1 hour
- Refactoring code due to the implementation of the bonus features : 1-2 hours.
