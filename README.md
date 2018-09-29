# files

Download and upload files using PHP server.

## Setup

```bash
# Move to your www directory
cd www

# Create new directory for this app
mkdir files

# Give write permission at least to web server user
chmod a+w files

cd files
git clone git@github.com:iqqmuT/simple-fileserver.git .engine
ln -s .engine/index.php
```

Now try it: `http://localhost/files/`.
