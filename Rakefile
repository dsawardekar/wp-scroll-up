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
  'composer:update',
  'bower:update',
  'git:ignore',
  'git:vendor'
]
