# systemd deployment

`htpc.service` runs `next start` (compiled production build) as user
`nathaniel`, binding to `127.0.0.1:3000`. Put a reverse proxy in front of
it (nginx / Caddy / Cloudflare Tunnel) if you want it externally reachable.

## Install

```sh
# 1. Build the production bundle (this is what `next start` serves).
cd /space/git/happytailspawcare.com/dev
npm run build

# 2. Install the unit (root needed once; the service itself runs as nathaniel).
sudo install -m 0644 deploy/htpc.service /etc/systemd/system/htpc.service
sudo systemctl daemon-reload
sudo systemctl enable --now htpc

# 3. Verify
systemctl status htpc
journalctl -u htpc -f
curl -I http://127.0.0.1:3000/
```

## Updating

```sh
git pull
npm install
npm run build
sudo systemctl restart htpc
```

## Notes

- **Node path is hard-coded** to the current nvm install
  (`/home/nathaniel/.nvm/versions/node/v24.5.0/bin/node`). If you upgrade
  Node via `nvm install …`, update the two `Environment=` lines and the
  `ExecStart=` path. As a sturdier alternative, symlink one stable path:
  `sudo ln -sf /home/nathaniel/.nvm/versions/node/v24.5.0/bin/node /usr/local/bin/node`
  and change `ExecStart=` to use `/usr/local/bin/node`.

- **Port** defaults to 3000. To change, edit `Environment=PORT=…` in the
  unit and run `daemon-reload && restart`.

- **Env file**: the unit reads `.env` from the project root. Make sure
  `PAYLOAD_SECRET` and `DATABASE_URI` are set there (they already are
  after `cp .env.example .env`).

- **Database is SQLite** (`./payload.db`). The unit grants RW access to
  the project directory only. If you move the DB elsewhere, update
  `ReadWritePaths=`.

- **First admin user**: hit `https://your-domain/admin` after the service
  is up; Payload will prompt you to create one. Or set `ADMIN_EMAIL` and
  `ADMIN_PASSWORD` in `.env` and run `npm run import:wp` once to seed
  the user (and re-import content if the DB is empty).
