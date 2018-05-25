VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

    config.vm.box = "CircleBilling/CircleBilling"
    config.vm.box_version = "1.1.0"
    config.vm.box_check_update = false

    # Do some network configuration
    config.vm.network "private_network", ip: "192.168.45.10"

    config.vm.synced_folder "", "/var/www/circlebilling.local"
end
