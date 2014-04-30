
namespace :git do
  desc "Update .gitignore"
  task :ignore do
    cp 'lib/templates/gitignore', '.gitignore'
  end

  task :vendor do
    sh 'git add vendor'
    sh 'git commit -m "Adds vendor"'
  end
end
