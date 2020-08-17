## Algs E.4 PHP Code Note

<This is not completed yet, to be elaborated...>

### Programming model
Indexed array only, only when nessisary
尽量与原书变量名一致
Comparable -> sgh/comparable
Only Algs namespace
@todo When neccessary, use SplFixedArray (FixedCapacityStackOfStrings)
Arr
__constructStatic()
使用了 7.2 的特性
function overload
- sort and doSort
- fromThis, fromThat

做除法时一定要注意是否需要强转成 (int)
= null -> unset()

### PHP Sucks?
- 泛型 (Stack<String>, Stack<Double>)
- 面向对象 (String.charAt())
- 多字节支持 ([] 对中文无用)
- `$$$$$$`, `$this`

## Code Style
尽量与原书一直: 缩进, 省略, 变量名

## 搭建, 运行

## Mock Java

- Static block: <https://stackoverflow.com/questions/3312806/static-class-initializer-in-php/3312881#3312881>
- 只实现了书中必要的库方法
- 数组引用
- main() -> **Test::run()

## Autoload:
- Composer autoload
- <https://stackoverflow.com/questions/41496727/how-to-force-php-cli-to-read-my-user-ini-file>
- <https://symfony.com/doc/current/components/var_dumper.html>
- .user.ini, bash_profile

## Composer packages