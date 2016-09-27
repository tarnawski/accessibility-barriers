Accessibility Barriers API
==========================

###Getting started

In order to set application up you must follow by steps:

1. Install VirtualBox, Vagrant,
2. Install the following vagrant plugins
    - Vagrant WinNFSd: `vagrant plugin install vagrant-winnfsd`
3. Go to vagrant directory: `cd vagrant`
4. Run Vagrant Virtual Machine `vagrant up`

### How to provision new server
```bash
ansible-playbook -i hosts provision.yml -u {USERNAME} -k -K
```

### Create archive with project 
```
composer archive --format=tar --file=accessibility-barriers --dir=vagrant/provisioning/
```
### Deploy App
```
sudo ansible-galaxy install --force carlosbuenosvinos.ansistrano-deploy
ansible-playbook -i hosts deploy.yml -u {USERNAME} -k -K
```