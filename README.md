ideas:
- make the maskot interractable: make a hat on the bottom-left that has blinking eyes from time-to-time and when it is clicked then the phoenix "POOFs" and appears. Make it be able to be moved around and de-summonned
- make the top part of the homepage animated on scroll with a short animation - probably using svg animations like 1 of the saved examples
- create emojis so that the visitors can react to projects/posts
- add an interactive way to have a hyperlink to the live project in the project's page - maybe have the mascot say something and guide you to it, or have an interactive hover/popyp/button that is not tied to the project's description that will lead you to the live project/website
- project card with rounded edges (rectangular), with image full-size, on-hover effect (burn effect from the center outwards that reveals the name)

multipager:
- homepage
- projects
- services - dropdown with all services
- about me
- admin panel where everything can be controlled

footer with contacts + "ask me anything" form popup

breakdown of the pages:
- homepage:
    - a little from everything (in progress..)

- projects:
    - the main page will have projects cards with filter by label
    - each card will have only an image, title and labels
    - each project will have it's own page - /projects/{project_slug}
    - the project page will have: title, description (text), used technologies (can be icons) and image gallery (either carousel or grid)

- services:
    - each service will have it's own page
    - content to be discussed

- about me:
    - a biography page with background/origin story, interests, hobbies, etc.

- admin panel
    - page to cistomize every aspect of the website, all the visible content


Notes:
- bulgarian is going to be the primary language, english is secondary
- every project is managed separately from eachother. Even if a project has the same images as another, they are gonna be treated as different images and related only to 1 project
- the images of the projects must be able to be ordered
- the cover better be set separetelly and speciffically for that purpose
- at first, each project needs to have a cover image, title

font family:
// EB Garamond
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">

.eb-garamond-<uniquifier> {
  font-family: "EB Garamond", serif;
  font-optical-sizing: auto;
  font-weight: <weight>;
  font-style: normal;
}

// Manrope
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">

.manrope-<uniquifier> {
  font-family: "Manrope", sans-serif;
  font-optical-sizing: auto;
  font-weight: <weight>;
  font-style: normal;
}