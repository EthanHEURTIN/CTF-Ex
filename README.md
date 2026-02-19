
<div align="center">
<hr>
<h3 align="center">CTF-Ex</h3>

If you're a <a href="https://x.com/MarexLoL">@MarexLoL</a> fan, this Capture The Flag is done for you 😉 !
<br/>
<br/>
<img src="https://cdn.betterttv.net/emote/6279756b3c6f14b688476306/3x.webp" alt="Logo" width="100" height="100">
<br/>
<hr>
<br/>
</div>

## About The Project

It's after taking courses of Cloud Computing where I learned in detail how to use Ansible and Docker that I decided to build this Capture the Flag. At the same time, I had cyber classes where we had a CTF in practical work. My friends started to be interested in CTF so it was a great occasion so it was a good opportunity to introduce them some interesting vulnerabilities I encountered. For a long time now, we followed a famous French Streamer named [@MarexLoL](https://x.com/MarexLoL) and we have a lot of references between us around his stream. To teach them stuff and make it fun, I built the CTF-Ex !

### Infrastructure
In order to deploy this CTF, you must have a Virtual Private Server (VPS) or whatever you want. The project has been made to be easily deploy on a VPS. The players just need to have an OpenVPN certificate to access the lab on the VPS.
**My deployment tests were carried out on an OVHcloud VPS.** Ansible is launched locally, on the VPS.

The Ansible playbook :
* Installs Docker on the VPS with all dependencies
* Creates the differents networks (the OpenVPN network and the lab network)
* Creates three containers :
	1. **The OpenVPN container** : This container manages the OpenVPN tunnel.
	2. **The first container** : The entrypoint of the lab (the bastion).
	3. **The second container** : The second machine of the lab.
* Generates the OpenVPN certificates

## Prerequisites

You first need to install Ansible on your VPS :
```bash
# For Debian
sudo apt install ansible-core -y
```
*For others distributions, check the Ansible documentation.*

## Setup

These are the steps to deploy the CTF-Ex on your machine :

1. Clone the repository :
```bash
git clone https://github.com/EthanHEURTIN/CTF-Ex.git
```

2. Access the repository :
```bash
cd CTF-Ex
```

3. You should have this :
```bash
.
├── group_vars
│   └── all.yml
├── playbook.yml
├── roles
│   ├── cleaner
│   │   └── tasks
│   │       └── main.yml
│   ├── containers
│   │   ├── files
│   │   │   ├── container1
│   │   │   │   ├── Dockerfile
│   │   │   │   └── [...SNIP...]
│   │   │   └── container2
│   │   │       ├── Dockerfile
│   │   │       └── [...SNIP...]
│   │   └── tasks
│   │       └── main.yml
│   ├── docker
│   │   └── tasks
│   │       └── main.yml
│   └── openvpn
│       └── tasks
│           └── main.yml
└── setup.sh
```

## Usage

The bash script `setup.sh` is the core of the repository. It will asks you the VPS public IP address used for the OpenVPN certificates.
The help menu :
```bash
$ ./setup.sh -h
Usage: ./setup.sh [options]

Options:
  --nuke              (Cleanup everything (containers, networks, images, OpenVPN configuration) and restart everything)
  --restart-c1        (Restart the first container without rebuilding image)
  --restart-c2        (Restart the second container without rebuilding image)
  --restart-all       (Restart all the Lab containers)
  -h, --help          (Show this help menu)
```

The bash script `setup.sh` gonna asks you to modify a `clients.txt` file. **You have to put one name on each line !** A name is equivalent to a certificate. 

*Example : If you want to deploy CTF-Ex for you (Alexis) and two of your friends (Valentin and Adrien), your `clients.txt` file gonna be :*
```text
alexis
valentin
adrien
```

The certificates files will be created in the `clients` directory. Just give them to your friends and execute him !
After that, the bash script will run the Ansible playbook depending of the options you chose.

## CTF Scenario

![:POLICE:](https://cdn.discordapp.com/emojis/1171461876429889638.webp?size=20&animated=true) **YOUR ATTENTION PLEASE** ![:POLICE:](https://cdn.discordapp.com/emojis/1171461876429889638.webp?size=20&animated=true) 

Welcome in the CTF-Ex ! Your objective is to take control of all the machines in the Lab and retrieve the 8 flags scattered across the machines. You will find yourself in the VPN tunnel `10.8.0.0/24`. The CTF entry point is the subnet `172.16.10.0/24`. 

Good luck ! ![:aga:](https://cdn.discordapp.com/emojis/1454841722965331979.webp?size=20) (be careful of rabbit holes... ![:PRANKEX:](https://cdn.discordapp.com/emojis/973591358747074580.webp?size=20&animated=true))

## Special thanks

Thanks to Dinohh for giving me access to Dollex.io's ASCIIS while the site was undergoing maintenance !
## License

Distributed under the MIT License. See [MIT License](https://opensource.org/licenses/MIT) for more information.
