Cloudflare Tunnel (cloudflared) — quick setup

1) Prerequisites
- Have a Cloudflare account with your domain added to Cloudflare.
- `docker` and `docker compose` installed locally.

2) Authenticate & create a tunnel (run on your workstation)
- Install `cloudflared` (or use the Docker image).
- Login and create a tunnel:

  # login in browser (recommended)
  cloudflared login

  # create a named tunnel (example name: my-wp-tunnel)
  cloudflared tunnel create my-wp-tunnel

  # this prints a credentials file like ~/.cloudflared/<TUNNEL-ID>.json

3) Route DNS to the tunnel
- Point your hostname (e.g. `example.com`) to the tunnel:

  cloudflared tunnel route dns my-wp-tunnel example.com

4) Prepare the repo files
- Copy the credentials file into this repo's `cloudflared` folder and rename it to match `TUNNEL_ID_PLACEHOLDER.json` (or edit `cloudflared/config.yml` to point to the real filename). Example:

  mkdir -p cloudflared
  cp ~/.cloudflared/<TUNNEL-ID>.json ./cloudflared/TUNNEL_ID_PLACEHOLDER.json

- Edit `cloudflared/config.yml` and replace:
  - `TUNNEL_NAME_PLACEHOLDER` with `my-wp-tunnel` (or your tunnel name)
  - `TUNNEL_ID_PLACEHOLDER.json` with the actual credentials filename
  - `example.com` with your real domain, and optionally remove the phpmyadmin host if you don't want it exposed.

5) Run the services
- If your WordPress stack is not running yet:

  docker compose up -d

- If you already have the stack running, start just the tunnel service:

  docker compose up -d cloudflared

6) Notes & security
- `cloudflared` will proxy the hostname(s) defined in `config.yml` to the services by service name (`wordpress`, `phpmyadmin`) inside the compose network.
- Do not commit your credentials JSON to git.
- Consider using Cloudflare Access / zero-trust rules to protect admin routes (wp-admin, phpMyAdmin).
