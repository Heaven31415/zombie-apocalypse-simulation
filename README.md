# Zombie Apocalypse Simulation

Zombie apocalypse simulation written in PHP and Symfony with a `docker-compose`
support for building and running it

## How does the simulation work?

The entire simulation is a small (20 in width and 20 in height) 2D world divided
into equal square cells. In each cell unlimited number of entities (humans, zombies
and resources) can be placed. Each kind of entity behaves in its own unique way, described
down below:

### Humans

- move away from zombies (twice as fast if they have eaten food)
- try to pick up available resources
- shoot zombies if they are close enough and ammo is available (1 bullet is needed to kill a single zombie)

### Zombies

- chase humans
- wander randomly around the world if no humans are left
- bite humans that are in the exact same cell (which instantly turns them into zombies)

### Resources

- increase the number of ammo or food after being picked up by humans

State of the simulation is updated regularly every 1 second

## How to build and run the simulation?

1. Install `docker` and `docker-compose` on your machine (Make sure that docker engine is running)
2. Open a new terminal session in a folder with `docker-compose.yml` file
3. Run the following command: `$ docker-compose up`
4. Wait a few dozen seconds until all 3 containers are ready and running
5. Open you browser and go to [http://localhost:8000/](http://localhost:8000/) (Make sure that you use
HTTP protocol, unfortunately HTTPS is not supported out of the box)
6. You should be able to see a page with the menu and the simulation

## How to interact with the simulation?

Below the menu you will find 6 links to add different kind
of entities to the simulation or to see their current state
