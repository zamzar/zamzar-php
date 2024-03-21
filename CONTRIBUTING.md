# How to contribute

We would greatly value feedback and contributions from our community.

## Running tests

To run tests:

- Install [Docker Desktop](https://www.docker.com/products/docker-desktop/)

- Run `make test`. This will start a docker compose stack (including a [zamzar-mock](https://github.com/zamzar/zamzar-mock)
  container) and run the tests against it.

## Releasing a new version

PRs to this repository should be made against the `main` branch and labelled with:

* `bump:patch` for bug fixes
* `bump:minor` for new features
* `bump:major` for breaking changes

When a PR is merged, the CI will automatically tag a new version. 
[Packagist](https://packagist.org/packages/zamzar/zamzar-php) is informed via a GitHub webhook (configured on this repo) and will update the package accordingly.
