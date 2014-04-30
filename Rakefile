namespace :git do
  desc "Update .gitignore"
  task :ignore do
    cp 'lib/templates/gitignore', '.gitignore'
    sh 'git add .gitignore'
    sh 'git commit -m "Updates .gitignore"'
  end

  task :vendor do
    sh 'git add vendor'
    sh 'git commit -m "Adds vendor"'
  end

  task :clean do
    sh 'rm -rf tmp' if File.directory?('tmp')
    sh 'rm -rf bower_components' if File.directory?('bower_components')
    sh 'rm -rf vendor' if File.directory?('vendor')

    sh 'git rm *.json'
    sh 'git rm *.lock'
    sh 'git rm -r test'
    sh 'git rm -r bin'
    sh 'git rm phpunit.xml'
    sh 'git rm Gemfile'
    sh 'git rm Rakefile'

    sh 'git commit -m "Removes development files"'
  end

  # todo: conditionally add js libs
  task :js do
  end
end

namespace :bower do
  desc "Copy Bower libraries"
  task :copy do
    cp 'bower_components/scrollUp/js/jQuery.scrollUp.js', 'js/jquery-scroll-up.js'
  end

  desc "Update Bower libraries"
  task :update do
    sh 'bower update'
  end
end

namespace :composer do
  desc "Update Composer dependencies"
  task :update do
    sh 'composer update'
  end
end

desc 'Create a new Distribution'
task :dist => [
  'git:clean',
  'git:ignore',
  'composer:update',
  'git:vendor'
]

desc 'Initialize - after distribution'
task :init => [
  'composer:update',
  'bower:update'
]
