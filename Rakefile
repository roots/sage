require "rubygems"
require "bundler"
Bundler.setup

# Settings for Compass and rsync deployment
css_dir               = "stylesheets/css"
theme                 = "roots"
ssh_user              = "username"
remote_root           = "~/path/on/server"

namespace :styles do
  
  desc "Run compass stats"
  task :stats => ["stats:default"]

  namespace :stats do

    task :default do
      puts "*** Running compass stats ***"
      system "compass stats"
    end

    desc "Create a log of compass stats"
    task :log do
      t = DateTime.now
      filename = "compass-stats-#{t.strftime("%Y%m%d")}-#{t.strftime("%H%M%S")}.log"
      puts "*** Logging stats ***"
      system "compass stats > log/#{filename}"
      puts "Created log/#{filename}"
    end
    
  end
  
  desc "Clear the styles"
  task :clear => ["compile:clear"]
  
  desc "List the styles"
  task :list do
    system "ls -lh #{css_dir}"
  end
  
  desc "Compile new styles"
  task :compile => ["compile:default"]

  namespace :compile do
    
    task :clear do
      puts "*** Clearing styles ***"
      system "rm -Rfv #{css_dir}/*"
    end

    task :default => :clear do
      puts "*** Compiling styles ***"
      system "compass compile"
    end

    desc "Compile new styles for production"
    task :production => :clear do
      puts "*** Compiling styles ***"
      system "compass compile --output-style compressed --force"
    end

  end
  
end

desc "Clears and generates new styles, builds and deploys"
task :deploy do
  puts "*** Deploying the site ***"
  system "rsync -avz --delete . #{ssh_user}:#{remote_root}/wp-content/themes/#{theme}/"
end