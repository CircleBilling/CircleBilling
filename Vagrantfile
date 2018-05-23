VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.box = "CircleBilling/CircleBilling"
  config.vm.box_version = "1.1.0"
  config.vm.box_check_update = false

    if Vagrant::Util::Platform.windows?
        config.vm.synced_folder ".", "/vagrant",
            id: "core",
            :nfs => true,
            :mount_options => ['dmode=777','fmode=777']
    else
        config.vm.synced_folder ".", "/vagrant",
            id: "core",
            :nfs => false,
            :mount_options => ['dmode=777','fmode=777']
    end

    # Do some network configuration
    config.vm.network "private_network", ip: "192.168.45.10"

    config.vm.synced_folder "", "/var/www/circlebilling.local"
end
